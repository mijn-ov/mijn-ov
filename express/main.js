import express from "express";
import { ChatOpenAI } from "@langchain/openai";
import { ChatPromptTemplate, MessagesPlaceholder } from "@langchain/core/prompts";
import { StructuredOutputParser } from "langchain/output_parsers";
import { z } from "zod";
import {createOpenAPIChain} from "langchain/chains";
import * as fs from "fs";
import yaml from "js-yaml";
import {AIMessage, HumanMessage} from "@langchain/core/messages";
import { createOpenAIFunctionsAgent } from "langchain/agents";
import { ChatMessageHistory } from "langchain/stores/message/in_memory";
import { RunnableSequence } from "@langchain/core/runnables";
import fetchMovies from "./functions/getMovieImages.js";
//memory imports
import { BufferMemory } from "langchain/memory";
import { UpstashRedisChatMessageHistory } from "@langchain/community/stores/message/upstash_redis";

const router = express.Router();
// POST endpoint for user input
const chatHistory = new ChatMessageHistory();
router.post('/recommendations', async (req, res) => {
    try {


        // user input from front end
        const userInput = req.body.userInput;
        let totalTokens;

        // Create the model
        const model = new ChatOpenAI({
            azureOpenAIApiKey: process.env.AZURE_OPENAI_API_KEY,
            azureOpenAIApiVersion: process.env.OPENAI_API_VERSION,
            azureOpenAIApiInstanceName: process.env.INSTANCE_NAME,
            azureOpenAIApiDeploymentName: process.env.ENGINE_NAME,
            temperature: 0.2,
            callbacks: [
                {
                    handleLLMEnd(output) {
                        totalTokens =  (JSON.stringify(output.llmOutput.tokenUsage.totalTokens));
                    },
                },
            ],
        });


        //Prompt Template
        const prompt = ChatPromptTemplate.fromTemplate(
            `You are a public transport chatbot, that will awnser any questions related to time, errors, etc.
            For now you will also awnser with random info if the user asks for this. meaning you come up with not real info just to test.
                     You will also check for any info on other alternatives if possible 
                     Before doing anything i want you to check our previous conversation: {history}. This is Json and has both a HumanMessage and AIMessage. Its an array meaning the first
                     messages where our first chat messages, look in this content to generate better results to our chats.
                     The users information/question about public transport: {userInput}
                     Format: {formatInstructions}
                    `
        );

        const upstashChatHistory = new UpstashRedisChatMessageHistory({
            sessionId: 'chat1',
            config: {
                url: process.env.UPSTASH_REDIS_REST_URL,
                token: process.env.UPSTASH_REDIS_REST_TOKEN,
            }
        })

        const memory = new BufferMemory({
            memoryKey: "history",
            chatHistory: upstashChatHistory,
        });

        // Parser
        const parser = StructuredOutputParser.fromZodSchema(
            z.object({
                recommendations: z.array(
                    z.object({
                        title: z.string().describe("Title for info(very short)"),
                        description: z.string().describe("All the info and a chat about what the user asked"),
                        info: z.string().describe("Put time info here, also if there is special stuff happening at the moment on a specific road, if there is none. just write random letters"),
                    })
                ).max(2).describe("If there is an alternative route, add this with the same info. Normally put one specific route with info, title, etc."),
                
            })
        );

        const formatInstructions = parser.getFormatInstructions();


        const chain = RunnableSequence.from([
            {
                userInput: (initialInput) => initialInput.userInput,
                memory: () => memory.loadMemoryVariables(),
                formatInstructions: (initialInput) => initialInput.formatInstructions, // Include format_instructions here
            },
            {
                userInput: (previousOutput) => previousOutput.userInput,
                history: (previousOutput) => previousOutput.memory.history,
                formatInstructions: (previousOutput) => previousOutput.formatInstructions, // Pass format_instructions to the next step
            },
            prompt,
            model,
        ]).pipe(parser);

        // const chain = prompt.pipe(model).pipe(parser);
        // await chatHistoryInfo.addMessage(new HumanMessage(userInput));
        // Invoke OpenAI model with the constructed prompt
        let routeRecommendations
        try {
            routeRecommendations = await chain.invoke({
                userInput,
                formatInstructions,
            });
        } catch (e) {
            console.log(e);
        }



        // This makes sure that the input and output are both seen as (1 key) important!
        let testInput = {
            userInput: userInput
        }
        let testOutput = {
            output: routeRecommendations.recommendations
        }

        // Saves the memory
        await memory.saveContext(testInput, {
            testOutput
        })


        // Function to fetch movie details and add poster path




        res.json({routeRecommendations, totalTokens: totalTokens});

    } catch (error) {
        console.error("Error generating route recommendations:", error);
        res.status(500).json({ error: "Failed to generate route recommendations" });
    }
});

export default router;

